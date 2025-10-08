<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Hospital Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Test Hospital Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="testForm">
                            @csrf
                            <div class="mb-3">
                                <label for="user_type" class="form-label">User Type</label>
                                <select id="user_type" name="user_type" class="form-control" required>
                                    <option value="hospital" selected>Hospital</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="login" class="form-label">Email</label>
                                <input type="text" id="login" name="login" class="form-control" 
                                       value="apollo.hospital@gmail.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" 
                                       value="apollo123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Test Login</button>
                        </form>
                        
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#testForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $.ajax({
                    url: '/test-login-form',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#result').html('<div class="alert alert-success"><pre>' + JSON.stringify(response, null, 2) + '</pre></div>');
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error occurred';
                        if (xhr.responseJSON) {
                            errorMsg = JSON.stringify(xhr.responseJSON, null, 2);
                        } else if (xhr.responseText) {
                            errorMsg = xhr.responseText;
                        }
                        $('#result').html('<div class="alert alert-danger"><pre>' + errorMsg + '</pre></div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
