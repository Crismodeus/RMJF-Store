cat > scan_headers.sh << 'EOF'
#!/usr/bin/env bash
BASE_URL=${1:-http://localhost/tiendav2}
echo "Escaneando cabeceras en $BASE_URL"
curl -s -D- "$BASE_URL" -o /dev/null | grep -E \
  "Strict-Transport|Content-Security|X-Frame|X-Content-Type|Referrer-Policy" || {
    echo "⚠ Falta alguna cabecera de seguridad."
    exit 1
  }
echo "✅ Todas las cabeceras presentes."
EOF
