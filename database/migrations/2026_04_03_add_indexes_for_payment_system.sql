-- ============================================
-- FIX #6: DATABASE SCHEMA IMPROVEMENTS
-- ============================================
-- Add indexes for faster MoMo callback & booking queries
-- Run this SQL on your database or create Laravel migration

-- ✅ Add index for faster MoMo callback lookup
ALTER TABLE `tbl_checkout` 
ADD INDEX `idx_transactionId` (`transactionId`);

-- ✅ Add composite index for booking status & date queries
ALTER TABLE `tbl_booking` 
ADD INDEX `idx_bookingStatus_date` (`bookingStatus`, `bookingDate`);

-- ✅ Verify data consistency after migration
-- Find bookings marked as paid but without transaction ID
SELECT 
    b.bookingId, 
    b.totalPrice, 
    c.paymentStatus, 
    c.transactionId, 
    c.paymentMethod,
    'WARNING: Paid but no transaction!' as 'Issue'
FROM tbl_booking b
LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId
WHERE c.paymentStatus = 'y' AND (c.transactionId IS NULL OR c.transactionId = '');

-- ✅ Optional: Clean up any bad data
-- (REVIEW BEFORE EXECUTING!)
-- UPDATE tbl_checkout SET paymentStatus = 'n' 
-- WHERE (transactionId IS NULL OR transactionId = '') AND paymentStatus = 'y';

-- ✅ Verify indexes were created
SHOW INDEX FROM tbl_checkout;
SHOW INDEX FROM tbl_booking;
