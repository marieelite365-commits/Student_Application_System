<!DOCTYPE html>
<html>
<head>
    <title>Applications List</title>
</head>
<body>

<h2>All Applications</h2>

@foreach($applications as $application)

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

        <p><b>Username:</b> {{ $application->username }}</p>

        <p><b>CNIC:</b> {{ $application->cnic }}</p>

        <p><b>Degree:</b> {{ $application->degree }}</p>

        <p><b>Image:</b></p>

        <img src="{{ asset('storage/' . $application->image) }}" width="150">

        <p><b>Document:</b></p>

        <a href="{{ asset('storage/' . $application->document) }}" target="_blank">
            View Document 📄
        </a>

    </div>

@endforeach

</body>
</html>