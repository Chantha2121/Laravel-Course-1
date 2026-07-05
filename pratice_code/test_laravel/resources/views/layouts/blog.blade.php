<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Blog App - @yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
      <a class="navbar-brand font-weight-bold" href="{{ route('posts.index') }}">Laravel Blog</a>
      <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
    </div>
  </nav>
  <div class="container">
    @yield('content')
  </div>
</body>

</html>