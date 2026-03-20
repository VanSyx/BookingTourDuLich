/**
 * ROUTES: Tìm kiếm (Search)
 */
const express = require('express');
const router = express.Router();
const SearchController = require('../controllers/searchController');

// GET /api/search - Tìm theo điểm đến & ngày
router.get('/', SearchController.search);

// GET /api/search/keyword - Tìm theo từ khóa
router.get('/keyword', SearchController.searchByKeyword);

module.exports = router;
