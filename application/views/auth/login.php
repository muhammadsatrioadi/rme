<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/modules/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/modules/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .hospital-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-hospital hospital-icon"></i>
            <h3 class="mb-0">Sistem RME</h3>
            <p class="mb-0">Rekam Medis Elektronik</p>
        </div>
        <div class="login-body">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('auth/login'); ?>
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" 
                           class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" 
                           id="username" 
                           name="username" 
                           value="<?php echo set_value('username'); ?>"
                           placeholder="Masukkan username">
                    <?php echo form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan password">
                    <?php echo form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            <?php echo form_close(); ?>

            <div class="text-center mt-3">
                <a href="<?php echo base_url('auth/forgot_password'); ?>" class="text-muted">
                    <i class="fas fa-key"></i> Lupa Password?
                </a>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/modules/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/modules/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>
</html>

