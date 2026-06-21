@extends('layouts.app_main')

@section('content')
<section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4">
            <h2 class="text-white text-center mb-4 text-uppercase">Finalizar Pagamento</h2>
            
            <form action="{{ route('payment.process') }}" method="POST" id="paymentForm" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="tournament_id" value="{{ $tournamentId }}">

                <div class="mb-3">
                    <label class="text-white">Método de Pagamento</label>
                    <select name="metodo" id="paymentMethod" class="form-select form-white-input" onchange="toggleFields()">
                        <option value="pix">PIX</option>
                        <option value="card">Cartão de Crédito</option>
                    </select>
                </div>

                <div id="cardFields" style="display: none;">
                    <input type="text" id="cardNumber" class="form-control mb-2 form-white-input" placeholder="Número do Cartão (16 dígitos)" maxlength="19" oninput="formatCard(this)">
                    <div class="row">
                        <div class="col-6"><input type="text" class="form-control mb-2 form-white-input" placeholder="MM/AA" maxlength="5"></div>
                        <div class="col-6"><input type="text" class="form-control mb-2 form-white-input" placeholder="CVV" maxlength="3"></div>
                    </div>
                    <div id="cardError" class="text-danger small mb-2" style="display: none;">Número de cartão inválido.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Confirmar Pagamento</button>
            </form>
        </div>
    </div>
</section>

<script>
function toggleFields() {
    const metodo = document.getElementById('paymentMethod').value;
    document.getElementById('cardFields').style.display = (metodo === 'card') ? 'block' : 'none';
}

function formatCard(input) {
    input.value = input.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
}

function validateForm() {
    const metodo = document.getElementById('paymentMethod').value;
    if (metodo !== 'card') return true;

    const number = document.getElementById('cardNumber').value.replace(/\s/g, '');
    let sum = 0;
    for (let i = 0; i < number.length; i++) {
        let intVal = parseInt(number.substr(i, 1));
        if (i % 2 === 0) { intVal *= 2; if (intVal > 9) intVal -= 9; }
        sum += intVal;
    }
    const isValid = (sum % 10 === 0 && number.length === 16);
    document.getElementById('cardError').style.display = isValid ? 'none' : 'block';
    return isValid;
}
</script>
@endsection