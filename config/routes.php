<?php 
// Eksekusi route statis
if (isset($routes[$request])) {
    if (is_callable($routes[$request])) {
        // Jika route adalah fungsi (Closure), langsung eksekusi
        call_user_func($routes[$request]);
    } else {
        // Jika bukan Closure, maka gunakan array dengan controller & method
        [$controller, $method] = $routes[$request];
        $controllers[$controller]->$method();
    }
    exit();
}

// Route dengan parameter dinamis: controller/method/{id}
if (preg_match('/^(\w+)\/(\w+)\/(\d+)$/', $request, $matches)) {
    [$full, $controller, $method, $id] = $matches;

    // Pastikan controller ada
    if (isset($controllers[$controller]) && method_exists($controllers[$controller], $method)) {
        $controllers[$controller]->$method($id);
        exit();
    }
}

http_response_code(404);
echo "404 Not Found";
