@extends('layouts.app_main')

@section('content')
    <div class="container py-5 text-center text-white">
        <h2>Scanner de Ingressos</h2>
        <p>Aponte a câmara para o QR Code do participante.</p>

        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let scanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);

        function onScanSuccess(decodedText, decodedResult) {
            scanner.clear().then(_ => {
                window.location.href = decodedText;
            }).catch(error => {
                console.error("Erro ao parar o scanner", error);
            });
        }

        scanner.render(onScanSuccess);
    </script>
@endsection