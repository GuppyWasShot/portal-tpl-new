<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .container {
            text-align: center;
            max-width: 600px;
        }
        
        .error-code {
            font-size: 150px;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: #f5576c;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">500</div>
        <h1>Terjadi Kesalahan</h1>
        <p>Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu dan sedang memperbaikinya.</p>
        <a href="<?php echo base_url('galeri'); ?>" class="btn">← Kembali ke Galeri</a>
    </div>
</body>
</html>

