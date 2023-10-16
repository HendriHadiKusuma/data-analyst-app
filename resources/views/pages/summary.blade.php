@extends('layouts.app')
@section('title', 'Summary Data')
@section('content')
<div class="container mt-5 ">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form>
                <div class="mb-3">
                    <label for="dataSet" class="form-label">Data Set (comma-separated):</label>
                    <input type="text" class="form-control" id="dataSet" name="dataSet">
                </div>
            </form>
            
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 offset-md-3">
            <!-- Add an empty container for the results -->
            <div id="results-container"></div>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Get references to HTML elements
        var dataSetInput = $('#dataSet');
        var resultsContainer = $('#results-container');
        // Function to update the results
        function updateResults() {
            
            var dataSet = dataSetInput.val().trim(); // Trim whitespace

            // Check if the dataset is empty
            if (dataSet === '') {
                // Display the custom message
                resultsContainer.html(`<p>Add more data</p>`);
                return; // Exit early, don't send the request
            }

            // Split the dataset into an array
            var dataArray = dataSet.split(',');

            // Check if there's only one data point (remove leading/trailing spaces)
            if (dataArray.length === 1 && dataArray[0].trim() !== '') {
                // Display "Add data" for a single data point
                resultsContainer.html(`<p>Add data</p>`);
                return; // Exit early, don't send the request
            }

            // Send the data to the server for analysis
            $.post('{{ route("summary-data") }}', {
                _token: '{{ csrf_token() }}',
                dataSet: dataSet
            }, function(data) {
                if (data.message) {
                    // Display the custom message
                    resultsContainer.html(`<p>${data.message}</p>`);
                } else {
                    // Update the results in the container
                    // Update the results in three separate blocks
resultsContainer.html(`
    <div class="row mt-3">
        <div class="col-md-4">
            <h5 class="text-center">Basic Statistics</h5>
            <ul class="list-group">
                <li class="list-group-item p-2">Count: ${data.count}</li>
                <li class="list-group-item p-2">Sum: ${data.sum}</li>
                <li class="list-group-item p-2">Median: ${data.median !== null ? data.median : 'Add data'}</li>
                <li class="list-group-item p-2">Mean: ${data.mean}</li>
            </ul>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">Min & Max</h5>
            <ul class="list-group">
                <li class="list-group-item p-2">Minimum: ${data.min}</li>
                <li class="list-group-item p-2">Maximum: ${data.max}</li>
            </ul>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">Distribution</h5>
            <ul class="list-group">
                <li class="list-group-item p-2">Mode: ${data.mode}</li>
                <li class="list-group-item p-2">Q1 (First Quartile): ${data.q1}</li>
                <li class="list-group-item p-2">Q3 (Third Quartile): ${data.q3}</li>
                <li class="list-group-item p-2">Standard Deviation: ${data.standardDeviation}</li>
                <li class="list-group-item p-2">Variance: ${data.variance}</li>
            </ul>
        </div>
    </div>
`);


                }
            }, 'json');
        }

        // Attach an input event listener to the data input field
        dataSetInput.on('input', updateResults);
    });
</script>
