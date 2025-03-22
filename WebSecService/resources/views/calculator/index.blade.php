@extends('layouts.master')

@section('title', 'Calculator')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Simple Calculator</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="calculatorForm">
                            <div class="mb-3">
                                <label for="number1" class="form-label">First Number</label>
                                <input type="number" class="form-control" id="number1" name="number1" required>
                            </div>
                            <div class="mb-3">
                                <label for="number2" class="form-label">Second Number</label>
                                <input type="number" class="form-control" id="number2" name="number2" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Operation</label>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary" onclick="calculate('+')">Add (+)</button>
                                    <button type="button" class="btn btn-primary" onclick="calculate('-')">Subtract (-)</button>
                                    <button type="button" class="btn btn-primary" onclick="calculate('*')">Multiply (×)</button>
                                    <button type="button" class="btn btn-primary" onclick="calculate('/')">Divide (÷)</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="result" class="form-label">Result</label>
                                <input type="text" class="form-control" id="result" readonly>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculate(operation) {
            // Get the input values
            const number1 = parseFloat(document.getElementById('number1').value);
            const number2 = parseFloat(document.getElementById('number2').value);
            let result;

            // Validate inputs
            if (isNaN(number1) || isNaN(number2)) {
                alert('Please enter valid numbers.');
                return;
            }

            // Perform the calculation based on the operation
            switch (operation) {
                case '+':
                    result = number1 + number2;
                    break;
                case '-':
                    result = number1 - number2;
                    break;
                case '*':
                    result = number1 * number2;
                    break;
                case '/':
                    if (number2 === 0) {
                        alert('Cannot divide by zero.');
                        return;
                    }
                    result = number1 / number2;
                    break;
                default:
                    result = 0;
            }

            // Display the result
            document.getElementById('result').value = result;
        }
    </script>
@endsection