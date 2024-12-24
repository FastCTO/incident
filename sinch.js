
// add requires
var express = require("express");
var router = express.Router();
router.post("/", function (req, res, next) {
  //echo the post
  res.json(req.body);
});
module.exports = router;
