<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stripe Payment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h4 class="mb-4">You will be charged ₹100</h4>
      <div class="card">
        <form action="{{route('checkout.credit-card')}}" method="post"   id="payment-form" class="p-4">
          @csrf
          <div class="mb-3">
            <label for="card-element" class="form-label">Card Details</label>
            <div id="card-element" class="form-control">
              <!-- Stripe Card Element will be inserted here -->
            </div>
            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
          </div>
          <button
            id="card-button"
            type="button"
            class="btn btn-primary w-100"
            data-secret="{{ $clientSecret }}">
            Pay ₹100
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
  const stripe = Stripe('{{ $publishableKey }}');
  const elements = stripe.elements();
  const card = elements.create('card', {
    style: {
      base: {
        fontSize: '16px',
        color: '#32325d',
        fontFamily: 'Arial, sans-serif',
        '::placeholder': {
          color: '#a0aec0',
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    }
  });

  // Mount card input into the DOM
  card.mount('#card-element');

  // Real-time validation errors
  card.on('change', event => {
    const displayError = document.getElementById('card-errors');
    displayError.textContent = event.error ? event.error.message : '';
  });

  const cardButton = document.getElementById('card-button');
  const clientSecret = cardButton.dataset.secret;

  cardButton.addEventListener('click', async () => {
    // 1. Create Payment Method with billing details
    const { error: pmError, paymentMethod } = await stripe.createPaymentMethod({
      type: 'card',
      card: card,
      billing_details: {
        name: 'Shaiv Roy',
        address: {
          line1: 'Gurhatta',
          city: 'Patna City',
          country: 'IN',
          postal_code: '800008'
        }
      }
    });

    if (pmError) {
      document.getElementById('card-errors').textContent = pmError.message;
      return;
    }
    const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
      payment_method: paymentMethod.id
    });

    if (confirmError) {
      document.getElementById('card-errors').textContent = confirmError.message;
    } else if (paymentIntent.status === 'succeeded') {
      alert('Payment successful!');
      document.getElementById('payment-form').submit(); // Optional: submit to server
    }
  });
</script>

</body>
</html>
