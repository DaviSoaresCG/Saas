<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <title>Bem-vindo ao ZapCatálogo</title>
</head>
<body style="margin: 0; padding: 0; min-width: 100%; width: 100% !important; background-color: #f7f5f3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">

    <!-- Wrapper Principal -->
    <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%" style="background-color: #f7f5f3; padding: 36px 16px;">
        <tr>
            <td align="center">
                <!-- Container do E-mail (Max 580px) -->
                <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%" style="max-width: 580px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #eeece8;">
                    
                    <!-- Cabeçalho com Logo ZapCatálogo -->
                    <tr>
                        <td style="padding: 36px 36px 20px 36px;">
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color: #37322f; border-radius: 10px; width: 36px; height: 36px;">
                                            <tr>
                                                <td align="center" valign="middle" style="color: #d7f56b; font-size: 17px; font-weight: bold; line-height: 1;">
                                                    &#128717;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align: middle; padding-left: 10px;">
                                        <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; color: #37322f; text-decoration: none; letter-spacing: -0.3px;">
                                            zap<span style="font-weight: 400; color: #605a57;">catálogo</span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Corpo da Mensagem -->
                    <tr>
                        <td style="padding: 10px 36px 36px 36px;">
                            <!-- Tag Eyebrow -->
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin-bottom: 16px;">
                                <tr>
                                    <td style="font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #71834f; background-color: #f4f8e6; padding: 4px 12px; border-radius: 9999px; border: 1px solid #d8dfc9;">
                                        CONTA ATIVADA
                                    </td>
                                </tr>
                            </table>

                            <!-- Título Principal -->
                            <h1 style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 24px; line-height: 1.35; font-weight: 700; color: #37322f; margin: 0 0 16px 0;">
                                Sua vitrine digital está pronta para vender!
                            </h1>

                            <!-- Saudação e Texto de Boas-Vindas -->
                            <p style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #514b46; margin: 0 0 20px 0;">
                                Olá, <strong style="color: #37322f;">{{ $user->name }}</strong>! É um prazer ter você conosco. Sua conta no <strong>ZapCatálogo</strong> foi criada com sucesso e seu ambiente exclusivo já está preparado para receber pedidos pelo WhatsApp.
                            </p>

                            <!-- Card de Dados da Loja -->
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%" style="background-color: #fbfaf9; border: 1px solid #eeece8; border-radius: 14px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%">
                                            <tr>
                                                <td style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #8c827a; padding-bottom: 4px;">
                                                    Loja
                                                </td>
                                                <td align="right" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #8c827a; padding-bottom: 4px;">
                                                    E-mail de Acesso
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; color: #37322f;">
                                                    {{ $user->nome_loja ?: 'Sua Loja' }}
                                                </td>
                                                <td align="right" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 500; color: #514b46;">
                                                    {{ $user->email }}
                                                </td>
                                            </tr>
                                            @if (!empty($user->slug))
                                            <tr>
                                                <td colspan="2" style="padding-top: 12px; border-top: 1px dashed #e8e5e0;">
                                                    <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #8c827a;">Subdomínio do Catálogo:</span>
                                                    <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 600; color: #71834f; margin-left: 4px;">
                                                        {{ $user->slug }}.{{ config('app.domain', 'zapcatalogo.com.br') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Botão de Ação Destacado (CTA) -->
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('dashboard', ['slug' => $user->slug]) }}" target="_blank"
                                           style="display: block; background-color: #37322f; color: #ffffff; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; padding: 15px 32px; border-radius: 9999px; text-align: center;">
                                            Acessar meu Painel de Controle &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Dicas Rápidas / Próximos Passos -->
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation" width="100%" style="background-color: #ffffff; border-radius: 12px; border: 1px solid #eeece8; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 700; color: #37322f; margin: 0 0 8px 0;">
                                            O que fazer no seu painel:
                                        </p>
                                        <ol style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #605a57; margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 4px;">Cadastre seus primeiros produtos com fotos, descrição e preço;</li>
                                            <li style="margin-bottom: 4px;">Confirme seu número de WhatsApp para receber as notificações de compra;</li>
                                            <li style="margin-bottom: 4px;">Compartilhe o link da sua vitrine na bio das suas redes sociais.</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>

                            <!-- Nota de Segurança e Acesso -->
                            <p style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #8c827a; margin: 0;">
                                <em>Dica: Ao acessar o painel pela primeira vez neste navegador, informe seu e-mail cadastrado ({{ $user->email }}) e a senha que você definiu no momento do cadastro.</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé do E-mail -->
                    <tr>
                        <td style="padding: 24px 36px 36px 36px; border-top: 1px solid #eeece8; text-align: center; color: #8c827a; font-size: 12px; line-height: 1.6;">
                            <p style="margin: 0 0 6px 0;">
                                Se você tiver qualquer dúvida ou precisar de ajuda, basta responder a este e-mail.
                            </p>
                            <p style="margin: 0; color: #a19a93;">
                                &copy; {{ date('Y') }} ZapCatálogo. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
