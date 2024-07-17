//缓存配置文件！！！！
const CACHE_NAME = 'myphpdemo';
const FILES_TO_CACHE = [
  '/',
  '/index.php',
  '/admin/component/footer.php',
  '/admin/component/sidebar.php',
  '/admin/component/topbar.php',
  '/admin/DBconn/database.php',
  '/admin/customers.php',
  '/admin/dishes.php',
  '/admin/home.php',
  '/admin/index.php',
  '/admin/login.php',
  '/admin/orders.php',
  '/admin/reservations.php',
  '/admin/tables.php',
  '/static/css/biulded_all.css', 
  '/static/css/fontawesome.min.css', 
  '/static/js/all.min.js', 
  '/static/js/jquery-3.7.1.min.js', 
  '/clientReser.php'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(FILES_TO_CACHE);
      })
  );
});

//清理旧缓存
self.addEventListener('activate', (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (cacheWhitelist.indexOf(key) === -1) {
          return caches.delete(key);
        }
      }));
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        return response || fetch(event.request);
      })
  );
});
