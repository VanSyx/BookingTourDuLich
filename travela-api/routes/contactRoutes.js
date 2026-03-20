/**
 * ROUTES: Liên hệ (Contact)
 */
const express = require('express');
const router = express.Router();
const ContactController = require('../controllers/contactController');

// POST /api/contacts - Gửi liên hệ
router.post('/', ContactController.create);

module.exports = router;
