BIỂU MẪU 04  
**DANH SÁCH KIỂM TRA BẢO MẬT CƠ BẢN**

| Tên dự án |  |
| :---- | :---- |
| **Nhóm** |  |
| **Người kiểm tra** |  |
| **Ngày** |  |

**1\. BẢO MẬT MẬT KHẨU**

| ☐ | Mật khẩu được HASH bằng bcrypt (salt rounds \>= 10\) | ✅ BẮT BUỘC |
| :---: | :---- | :---- |
| ☐ | KHÔNG lưu mật khẩu plain text trong database | ✅ BẮT BUỘC |
| ☐ | KHÔNG trả về mật khẩu (kể cả hash) trong response | ✅ BẮT BUỘC |
| ☐ | Có validation độ mạnh mật khẩu (\>= 8 ký tự) | ⭕ Khuyến khích |

**2\. BẢO MẬT JWT & TOKEN**

| ☐ | JWT\_SECRET được lưu trong file .env | ✅ BẮT BUỘC |
| :---: | :---- | :---- |
| ☐ | JWT\_SECRET đủ dài và phức tạp (\>= 32 ký tự) | ✅ BẮT BUỘC |
| ☐ | Token có thời hạn (expiresIn) | ✅ BẮT BUỘC |
| ☐ | KHÔNG lưu thông tin nhạy cảm trong payload | ✅ BẮT BUỘC |
| ☐ | Có xử lý token hết hạn (401) | ⭕ Khuyến khích |

**3\. BẢO MẬT FILE & GIT**

| ☐ | File .env đã thêm vào .gitignore | ✅ BẮT BUỘC |
| :---: | :---- | :---- |
| ☐ | KHÔNG có API key, secret trong code | ✅ BẮT BUỘC |
| ☐ | Có file .env.example làm mẫu | ⭕ Khuyến khích |
| ☐ | KHÔNG push node\_modules lên Git | ✅ BẮT BUỘC |

**4\. BẢO MẬT API**

| ☐ | Các route cần xác thực đã có middleware | ✅ BẮT BUỘC |
| :---: | :---- | :---- |
| ☐ | Validate TẤT CẢ input từ user (Joi/express-validator) | ✅ BẮT BUỘC |
| ☐ | CORS đã cấu hình đúng origin | ✅ BẮT BUỘC |
| ☐ | Thông báo lỗi không tiết lộ chi tiết hệ thống | ⭕ Khuyến khích |
| ☐ | Có rate limiting chống brute force | ⭕ Khuyến khích |

| ⚠️ CẢNH BÁO BẢO MẬT Nếu có bất kỳ mục BẮT BUỘC nào chưa được đánh dấu, dự án KHÔNG an toàn để triển khai\! |
| :---: |

