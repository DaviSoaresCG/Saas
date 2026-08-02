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
        if(!$product){
            return;
        }

        // 1. Decodifica a imagem via Intervention (funciona com Base64 puro, Data URI, etc.)
        $image = Image::decode($this->imageBase64);

        // 2. Redimensiona proporcionalmente caso a largura exceda 1200px (evita arquivos gigantes no servidor)
        $image->scaleDown(width: 1200);

        // 3. Converte a imagem para WebP com qualidade 80%
        $imageWebp = $image->encodeUsingFileExtension('webp', 80);

        // 4. Gera um nome único e higienizado para o arquivo (.webp)
        $skuSanitizado = Str::slug($product->sku);
        $nomeArquivo = $skuSanitizado . '_' . Str::random(20) . '.webp';
        $caminhoRelativo = 'produtos/' . $this->userId . '/' . $nomeArquivo;

        // 5. Salva o arquivo WebP no disco público
        Storage::disk('public')->put($caminhoRelativo, (string) $imageWebp);

        // 6. Apaga a foto antiga se existir (para economizar espaço)
        if ($product->foto_url) {
            $caminhoAntigo = str_replace('/storage/', '', $product->foto_url);
            Storage::disk('public')->delete($caminhoAntigo);
        }

        // 7. Atualiza o banco com a nova URL
        $product->update([
            'foto_url' => $caminhoRelativo
        ]);
    }
}
