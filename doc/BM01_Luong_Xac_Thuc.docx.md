BIỂU MẪU 01  
**THIẾT KẾ LUỒNG XÁC THỰC NGƯỜI DÙNG**

| Tên dự án |  | Nhóm |  |
| :---- | :---- | :---- | :---- |

**1\. THÔNG TIN API XÁC THỰC**

| Endpoint | Method | Mô tả | Request Body | Response |
| :---- | :---- | :---- | :---- | :---- |
| /api/xac-thuc/dang-ky | POST | Đăng ký tài khoản mới |  |  |
| /api/xac-thuc/dang-nhap | POST | Đăng nhập |  |  |
| /api/xac-thuc/dang-xuat | POST | Đăng xuất |  |  |
| /api/xac-thuc/ho-so | GET | Lấy thông tin người dùng |  |  |
| /api/xac-thuc/doi-mat-khau | PUT | Đổi mật khẩu |  |  |

**2\. CẤU TRÚC TOKEN JWT**

| Payload chứa | ☐ userId   ☐ email   ☐ hoTen   ☐ vaiTro   ☐ Khác: \_\_\_\_\_\_\_ |
| :---- | :---- |
| **Thời hạn token** | ☐ 1 ngày   ☐ 7 ngày   ☐ 30 ngày   ☐ Khác: \_\_\_\_\_\_\_ |
| **Lưu trữ token** | ☐ localStorage   ☐ sessionStorage   ☐ Cookie   ☐ Khác: \_\_\_\_\_\_\_ |

**3\. PHÂN QUYỀN NGƯỜI DÙNG**

| Vai trò | Quyền hạn | Trang được truy cập |
| :---- | :---- | :---- |
| **Admin** |  |  |
| **Nhân viên** |  |  |
| **Khách hàng** |  |  |
| **Khách (chưa đăng nhập)** |  |  |

**4\. SƠ ĐỒ LUỒNG XÁC THỰC**

*(Vẽ sơ đồ luồng từ Đăng nhập → Lưu token → Gọi API → Xác minh → Response)*

|   |
| :---- |

