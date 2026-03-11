<?php
namespace App\Libraries;

/**
 * MAILER — Envoi d'emails HTML.
 * Utilise mail() de PHP. Nécessite un serveur SMTP configuré.
 */
class Mailer
{
    /**
     * Envoyer un email HTML.
     */
    public static function envoyerMail(string $destinataire, string $sujet, string $message): bool
    {
        // Headers pour email HTML en UTF-8
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: EnchereAPorter <noreply@enchereaporter.getcet.fr>\r\n";

        // Template simple
        $html = '<html><body style="font-family:Arial;padding:20px;">'
            . '<div style="max-width:600px;margin:0 auto;border:1px solid #ddd;border-radius:8px;padding:20px;">'
            . $message
            . '<hr style="border:none;border-top:1px solid #eee;margin:20px 0;">'
            . '<p style="font-size:12px;color:#999;text-align:center;">EnchèreAPorter — Ville de Getcet</p>'
            . '</div></body></html>';

        // Envoi (@ = pas d'erreur PHP si ça échoue)
        $ok = @mail($destinataire, $sujet, $html, $headers);

        // Log
        log_message($ok ? 'info' : 'error', "[MAILER] " . ($ok ? 'OK' : 'ECHEC') . " -> $destinataire : $sujet");

        return $ok;
    }
}
