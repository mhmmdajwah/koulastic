<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->get('name') }}_show" class="form-label">{{ $attributes->get('label') }}</label>

    <!-- Input yang ditampilkan ke user -->
    <input value="{{ $attributes->get('value') }}" type="text" class="form-control currency-input"
        data-hidden-id="{{ $attributes->get('name') }}" placeholder="{{ $attributes->get('placeholder') }}" required>

    <!-- Input tersembunyi yang dikirim ke server -->
    <input value="{{ $attributes->get('value') }}" type="hidden" name="{{ $attributes->get('name') }}"
        id="{{ $attributes->get('name') }}">
</div>

<script>
    document.querySelectorAll('.currency-input').forEach(input => {
        const hiddenInputId = input.getAttribute('data-hidden-id');
        const hiddenInput = document.getElementById(hiddenInputId);

        function formatAndSync(value) {
            let raw = value.replace(/[^0-9]/g, '');
            if (raw) {
                let formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(raw);
                input.value = formatted;
                hiddenInput.value = raw;
            } else {
                input.value = '';
                hiddenInput.value = '';
            }
        }

        // 🔹 Format saat halaman pertama kali dimuat (jika value sudah ada)
        if (input.value) {
            formatAndSync(input.value);
        }

        // 🔹 Format saat user mengetik
        input.addEventListener('input', function() {
            formatAndSync(this.value);
        });
    });
</script>
