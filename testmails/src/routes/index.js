const Router = require('express');
const mailsrouter = require('./mails/mails');

const router = Router();

router.use('/send',mailsrouter);

module.exports = router;