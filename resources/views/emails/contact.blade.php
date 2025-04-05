<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Nouveau message - Under The Flow</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8fafc; color: #111;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0"
          style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07); padding: 40px;">
          <tr>
            <td align="center" style="padding-bottom: 30px;">
              <h1 style="margin: 0; font-size: 24px; color: #1e40af;">🎤 Nouveau message via Under The Flow</h1>
            </td>
          </tr>

          <tr>
            <td style="font-size: 16px; line-height: 1.6;">
              <p><strong style="color: #1f2937;">👤 Nom :</strong> {{ $name }}</p>
              <p><strong style="color: #1f2937;">📧 Email :</strong> <a href="mailto:{{ $email }}"
                  style="color: #2563eb;">{{ $email }}</a></p>
              <p><strong style="color: #1f2937;">📝 Sujet :</strong> {{ $subject }}</p>
              <hr style="margin: 24px 0; border: none; border-top: 1px solid #e5e7eb;" />
              <p style="white-space: pre-line;"><strong style="color: #1f2937;">💬 Message :</strong><br>{{ $body }}</p>
            </td>
          </tr>

          <tr>
            <td align="center" style="padding-top: 40px; font-size: 14px; color: #9ca3af;">
              <p style="margin: 0;">© {{ now()->year }} Under The Flow — Créé avec ❤️ depuis les vibes</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>

</html>