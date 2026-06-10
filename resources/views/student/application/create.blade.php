<!DOCTYPE html>
<html>
<head>
    <title>Student Application Form</title>
</head>
<body>

<h2>Student Application Form</h2>

@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data">
    @csrf

    <h3>Academic Information</h3>

    <label>Degree Program:</label>
    <input type="text" name="degree_program" placeholder="BS, MS, PhD"><br><br>

    <label>Department:</label>
    <input type="text" name="department" placeholder="Computer Science"><br><br>

    <label>Semester:</label>
    <select name="semester">
        <option value="">Select Semester</option>
        <option value="Fall">Fall</option>
        <option value="Spring">Spring</option>
        <option value="Summer">Summer</option>
    </select><br><br>

    <label>Admission Year:</label>
    <input type="number" name="admission_year" placeholder="2026"><br><br>

    <h3>Academic Background</h3>

    <label>Last Qualification:</label>
    <input type="text" name="last_qualification" placeholder="Matric, Inter, Bachelor"><br><br>

    <label>Last Institution:</label>
    <input type="text" name="last_institution" placeholder="School/College/University name"><br><br>

    <label>Last CGPA (optional):</label>
    <input type="number" step="0.01" name="last_cgpa" placeholder="3.5"><br><br>

    <label>Last Percentage (optional):</label>
    <input type="number" step="0.01" name="last_percentage" placeholder="85.5"><br><br>

    <label>Passing Year:</label>
    <input type="number" name="passing_year" placeholder="2024"><br><br>

    <h3>Documents</h3>

    <label>Profile Photo:</label>
    <input type="file" name="profile_photo" accept="image/*"><br><br>

    <label>CNIC Front:</label>
    <input type="file" name="cnic_front" accept="image/*,.pdf"><br><br>

    <label>CNIC Back:</label>
    <input type="file" name="cnic_back" accept="image/*,.pdf"><br><br>

    <label>Matric Certificate:</label>
    <input type="file" name="matric_certificate" accept="image/*,.pdf"><br><br>

    <label>Other Document:</label>
    <input type="file" name="other_document" accept="image/*,.pdf"><br><br>

    <button type="submit">Submit Application</button>
</form>

</body>
</html>