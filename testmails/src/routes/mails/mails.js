const nodemailer = require('nodemailer');

module.exports = async (req, res, next) => {
    try {
        const { mailPostman, pwdPostman, email, name, message } = req.body;
    
        let transporter = nodemailer.createTransport({
            host: 'smtp.gmail.com',
            port: 587,
            secure: false,
            auth: {
                user: mailPostman,
                pass: pwdPostman
            }
        });

        let info = await transporter.sendMail({
            from: mailPostman, // sender address,
            to: email,
            subject:`sercies access for ${name}`,
            // text: 'Hello World'
            text: message
        })

        console.log('Message sent: %s', info.messageId);
        // Message sent: <b658f8ca-6296-ccf4-8306-87d57a0b4321@example.com>

        // Preview only available when sending through an Ethereal account
        console.log('Preview URL: %s', nodemailer.getTestMessageUrl(info));
        // Preview URL: https://ethereal.email/message/WaQKMgKddxQDoou...
        res.json({
            state:true
        })
    } catch (error) {
        console.log(error)
        res.json({
            state:false
        })
    }
}