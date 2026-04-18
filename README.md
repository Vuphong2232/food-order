# Food Order - Website Đặt Đồ Ăn Online

## Giới thiệu
**Food Order** là website đặt đồ ăn online được xây dựng bằng **Laravel**.  
Dự án hỗ trợ người dùng xem món ăn, thêm vào giỏ hàng, đặt đơn, theo dõi trạng thái đơn hàng và đánh giá sản phẩm.  
Ngoài ra, hệ thống còn có khu vực **quản trị** để quản lý sản phẩm, danh mục, đơn hàng, thông báo và thống kê doanh thu.

## Thông tin dự án
- **Tên dự án:** Food Order
- **Loại dự án:** Website đặt đồ ăn
- **Công nghệ chính:** Laravel, PHP, MySQL, Blade, JavaScript, Tailwind CSS, Vite
- **Số thành viên nhóm:** 3

## Thành viên nhóm
- Thành viên 1: Vũ Văn Phong - 20222998
- Thành viên 2: Nguyễn Trung Chính - 20222999
- Thành viên 3: Nguyễn Trí Dũng - 20223155

## Chức năng chính

### Người dùng
- Đăng ký, đăng nhập, đăng xuất
- Quên mật khẩu
- Xem danh sách món ăn
- Lọc món ăn theo danh mục
- Lọc theo khoảng giá
- Xem sản phẩm bán chạy
- Xem chi tiết sản phẩm
- Thêm món ăn vào giỏ hàng
- Cập nhật số lượng trong giỏ hàng
- Xóa sản phẩm khỏi giỏ hàng
- Đặt hàng online
- Chọn phương thức thanh toán: `COD`, `Bank`, `VNPay`
- Xem lịch sử mua hàng
- Theo dõi trạng thái đơn hàng
- Xem thông báo
- Đánh giá sản phẩm sau khi hoàn thành đơn hàng
- Quản lý thông tin cá nhân và đổi mật khẩu

### Quản trị viên
- Chuyển sang chế độ admin
- Quản lý sản phẩm
- Quản lý danh mục
- Quản lý đơn hàng
- Cập nhật tiến trình đơn hàng: `received`, `preparing`, `shipping`, `completed`
- Xóa đơn hàng
- Xem thông báo hệ thống
- Xem báo cáo, thống kê doanh thu, số đơn và sản phẩm bán chạy theo tuần/tháng/năm

## Công nghệ sử dụng
- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Blade Template, JavaScript
- **Build tool:** Vite
- **CSS:** Tailwind CSS
- **Cơ sở dữ liệu:** MySQL
- **Quản lý package:** Composer, npm

## Cấu trúc chức năng tiêu biểu
- `AuthController`: xử lý xác thực người dùng
- `HomeController`: hiển thị trang chủ, lọc sản phẩm, sản phẩm bán chạy
- `ProductController`: quản lý sản phẩm
- `CategoryController`: quản lý danh mục
- `CartController`: quản lý giỏ hàng
- `OrderController`: đặt hàng, theo dõi đơn, lịch sử đơn, thống kê
- `ReviewController`: đánh giá sản phẩm

## Cài đặt dự án

### 1. Clone repository
```bash
git clone https://github.com/Vuphong2232/food-order.git
cd food-order
```

### 2. Cài đặt thư viện PHP
```bash
composer install
```

### 3. Cài đặt thư viện frontend
```bash
npm install
```

### 4. Tạo file môi trường
```bash
cp .env.example .env
```

### 5. Tạo key ứng dụng
```bash
php artisan key:generate
```

### 6. Cấu hình cơ sở dữ liệu trong file `.env`
Ví dụ:
```env
APP_NAME=FoodOrder
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_order
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Chạy migration
```bash
php artisan migrate
```

### 8. Tạo liên kết storage
```bash
php artisan storage:link
```

### 9. Chạy server
```bash
php artisan serve
```

### 10. Chạy Vite
```bash
npm run dev
```

Dự án sẽ chạy tại:
```bash
http://127.0.0.1:8000
```

## Tài khoản và phân quyền
Hệ thống có 2 vai trò chính:
- **User:** đặt món, theo dõi đơn, đánh giá sản phẩm
- **Admin:** quản lý sản phẩm, danh mục, đơn hàng, thông báo, thống kê

Admin có thể bật/tắt **admin mode** để truy cập giao diện quản trị.

## Quy trình đặt hàng
1. Người dùng đăng nhập vào hệ thống
2. Chọn món ăn muốn mua
3. Thêm món vào giỏ hàng
4. Cập nhật số lượng sản phẩm trong giỏ
5. Nhập thông tin nhận hàng
6. Chọn phương thức thanh toán
7. Xác nhận đặt đơn
8. Theo dõi trạng thái đơn hàng
9. Đánh giá sản phẩm sau khi đơn hoàn tất

## Thống kê và báo cáo
Phần quản trị hỗ trợ:
- Thống kê doanh thu
- Đếm số đơn hoàn thành
- Đếm số đơn đang xử lý
- Hiển thị top sản phẩm bán chạy
- Lọc thống kê theo tuần, tháng, năm

## Hướng phát triển
- Tích hợp thanh toán online hoàn chỉnh với VNPay
- Bổ sung tìm kiếm món ăn
- Thêm mã giảm giá, voucher
- Hỗ trợ theo dõi đơn hàng realtime
- Tối ưu giao diện trên thiết bị di động
- Bổ sung dashboard trực quan hơn cho quản trị viên

## Một số hình ảnh minh họa
- Trang đăng nhập / đăng ký
- Trang chủ danh sách món ăn
- Giỏ hàng
- Trang đặt hàng
- Lịch sử mua hàng
- Trang quản trị sản phẩm
- Trang thống kê

> Có thể thêm ảnh chụp màn hình dự án vào thư mục `public/images` hoặc upload lên GitHub rồi chèn vào đây.

## Kết luận
Food Order là một dự án web đặt đồ ăn được xây dựng theo mô hình thực tế, giúp nhóm rèn luyện kỹ năng phát triển ứng dụng web với Laravel, quản lý dữ liệu, xử lý nghiệp vụ đặt hàng và xây dựng giao diện cho cả người dùng lẫn quản trị viên.

## License
Dự án được phát triển cho mục đích học tập và báo cáo môn học.
