/**
 * ============================================
 * UTILITY: CÁC HÀM HỖ TRỢ CHUNG
 * ============================================
 */

/**
 * Bản đồ mã điểm đến → tên đầy đủ
 */
const destinationMap = {
  'dn': 'Đà Nẵng',
  'cd': 'Côn Đảo',
  'hn': 'Hà Nội',
  'hcm': 'TP. Hồ Chí Minh',
  'hl': 'Hạ Long',
  'nb': 'Ninh Bình',
  'pq': 'Phú Quốc',
  'dl': 'Đà Lạt',
  'qt': 'Quảng Trị',
  'kh': 'Khánh Hòa',
  'ct': 'Cần Thơ',
  'vt': 'Vũng Tàu',
  'qn': 'Quảng Ninh',
  'la': 'Lào Cai',
  'bd': 'Bình Định'
};

/**
 * Bản đồ mã thời gian tour → chuỗi hiển thị
 */
const timeMap = {
  '3n2d': '3 ngày 2 đêm',
  '4n3d': '4 ngày 3 đêm',
  '5n4d': '5 ngày 4 đêm'
};

/**
 * Chuyển đổi ngày từ định dạng d/m/Y sang Y-m-d
 * @param {string} dateStr - Ngày dạng "dd/mm/yyyy"
 * @returns {string} - Ngày dạng "yyyy-mm-dd"
 */
const formatDateToMySQL = (dateStr) => {
  if (!dateStr) return null;
  const parts = dateStr.split('/');
  if (parts.length !== 3) return dateStr; // Trả nguyên nếu không đúng format
  return `${parts[2]}-${parts[1]}-${parts[0]}`;
};

/**
 * Tính số ngày giữa 2 ngày
 * @param {string} startDate - Ngày bắt đầu (yyyy-mm-dd)
 * @param {string} endDate - Ngày kết thúc (yyyy-mm-dd)
 * @returns {string} - VD: "4 ngày 3 đêm"
 */
const calculateDuration = (startDate, endDate) => {
  const start = new Date(startDate);
  const end = new Date(endDate);
  const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
  const nights = days - 1;
  return `${days} ngày ${nights} đêm`;
};

/**
 * Tạo chuỗi ngẫu nhiên (dùng cho activation token)
 * @param {number} length - Độ dài chuỗi
 * @returns {string}
 */
const generateRandomString = (length = 60) => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
};

module.exports = {
  destinationMap,
  timeMap,
  formatDateToMySQL,
  calculateDuration,
  generateRandomString
};
