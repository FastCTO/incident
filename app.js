
// add requirements
var express = require("express");
var router = require("./routes/sinch.js");

//set up an app
var app = express();
//configure on what port express will create your app
var port = process.env.PORT || 5000;

//add the sinch route
app.use("/sinch.js", router);

//add default content type for all requests
app.use(function (req, res, next) {
  res.setHeader("Content-Type", "application/json");
  next();
});
//export and start listening
module.exports = app;
app.listen(port);
