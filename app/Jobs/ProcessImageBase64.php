<?php

namespace App\Jobs;

use App\Models\Products;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProcessImageBase64 implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    protected $productId;
    protected $userId;
    protected $imageBase64;

    /**
     * Create a new job instance.
     */
    public function __construct($productId, $userId, $imageBase64)
    {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->imageBase64 = $imageBase64;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Products::find($this->productId);
        if (!$product) {
            return;
        }

        try {
            $raw = trim($this->imageBase64 ?? '');
            if (empty($raw)) {
                return;
            }

            // 1. Remove cabeçalho Data URI se presente (ex: data:image/png;base64,)
            if (str_contains($raw, ';base64,')) {
                [, $raw] = explode(';base64,', $raw, 2);
            }

            // 2. Remove quebras de linha e espaços que corrompem o Base64
            $cleanBase64 = preg_replace('/\s+/', '', $raw);

            // 3. Decodifica para binário bruto
            $binaryData = base64_decode($cleanBase64, true);

            // Se o base64_decode retornar falso ou vazio, tenta os dados brutos
            $source = ($binaryData !== false && strlen($binaryData) > 0) ? $binaryData : $raw;

            // 4. Decodifica a imagem com fallback automático para Imagick se o GD falhar
            $image = null;
            try {
                $image = Image::decode($source);
            } catch (\Throwable $e) {
                if (extension_loaded('imagick') && class_exists(\Intervention\Image\ImageManager::class) && class_exists(\Intervention\Image\Drivers\Imagick\Driver::class)) {
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
                    $image = $manager->decode($source);
                } else {
                    throw $e;
                }
            }

            // 5. Redimensiona proporcionalmente caso a largura exceda 1200px
            $image->scaleDown(width: 1200);

            // 6. Converte a imagem para WebP com qualidade 80%
            $imageWebp = $image->encodeUsingFileExtension('webp', 80);

            // 7. Gera um nome único e higienizado para o arquivo (.webp)
            $skuSanitizado = Str::slug($product->sku ?: 'produto');
            $nomeArquivo = $skuSanitizado . '_' . Str::random(20) . '.webp';
            $caminhoRelativo = 'produtos/' . $this->userId . '/' . $nomeArquivo;

            // 8. Salva o arquivo WebP no disco público
            Storage::disk('public')->put($caminhoRelativo, (string) $imageWebp);

            // 9. Apaga a foto antiga se existir (para economizar espaço)
            if ($product->foto_url) {
                $caminhoAntigo = str_replace('/storage/', '', $product->foto_url);
                Storage::disk('public')->delete($caminhoAntigo);
            }

            // 10. Atualiza o banco com a nova URL
            $product->update([
                'foto_url' => $caminhoRelativo
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("ProcessImageBase64: Falha ao processar imagem do produto SKU: '{$product->sku}' (ID: {$product->id}). Motivo: " . $e->getMessage(), [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'data_length' => strlen($this->imageBase64 ?? ''),
                'prefix' => substr($this->imageBase64 ?? '', 0, 40),
            ]);
        }
    }
}
