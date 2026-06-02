<!DOCTYPE html>
<html>
<head>
    <title>Information Form</title>
</head>
<body>

<h2>Information Form</h2>

<form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data">
    @csrf

    <input type="text" name="username" placeholder="Username"><br><br>

    <input type="password" name="password" placeholder="Password"><br><br>

    <input type="text" name="cnic" placeholder="CNIC"><br><br>

    <input type="text" name="degree" placeholder="Degree"><br><br>

    <input type="number" name="age" placeholder="Age" class="input" /><br><br>

    <input type="text" name="phone" placeholder="Phone Number" class="input" /><br><br>

    <input type="text" name="program" placeholder="Interested Program" class="input" /><br><br>

    <label>Upload Image:</label>
    <input type="file" name="image"><br><br>

    <label>Upload Document:</label>
    <input type="file" name="document"><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>