const express = require('express');
const app = express();
const morgan = require('morgan');
const helmet = require('helmet');
const mails = require('./routes');

//security
app.disable('x-powered-by')
app.use(helmet());
app.set('trust proxy', 1);

//middlewares
app.use(express.json());
app.use(express.urlencoded({
    extended: true
}));
app.use(morgan('dev'));//develop use

//routes
app.use(mails);


module.exports = app;
