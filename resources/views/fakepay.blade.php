<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakePay — оплата заказа #{{ $payment->order_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-xl shadow-md max-w-md w-full text-center">
        <div class="text-3xl font-extrabold text-indigo-600 mb-1">⚡ FakePay</div>
        <p class="text-gray-500 text-sm mb-6">Тестовый платёжный шлюз. Деньги не списываются</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200 text-left space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Заказ:</span>
                <span class="font-semibold text-gray-800">#{{ $payment->order_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Сумма:</span>
                <span class="font-bold text-lg text-gray-900">
                    {{ number_format($payment->amount / 100, 2, ',', ' ') }} ₸
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Статус:</span>
                <span class="font-medium text-gray-800">{{ $payment->status }}</span>
            </div>
        </div>

        @if ($payment->status === 'pending')
            <div class="space-y-3">
                <form action="/fakepay/{{ $payment->provider_payment_id }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="paid">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-4 rounded-lg transition cursor-pointer">
                        ✅ Оплатить
                    </button>
                </form>

                <form action="/fakepay/{{ $payment->provider_payment_id }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="failed">
                    <button type="submit" class="w-full bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium py-3 px-4 rounded-lg transition cursor-pointer">
                        ❌ Отказ
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-600">Этот платёж уже обработан.</p>
        @endif
    </div>

</body>
</html>
