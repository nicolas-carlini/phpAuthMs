const Router = require('express');
const mailsrouter = require('./mails/mails');

const router = Router();

router.post('/send',mailsrouter);

module.exports = router;