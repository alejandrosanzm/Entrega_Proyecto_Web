var express    = require('express');
var bodyParser = require('body-parser');
var mysql      = require('mysql');
var app        = express();
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '',
  database: "letters"
});

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.get("/getAll", (req, res)=>{
  var query = "SELECT letters.id, letters.writer, letters.letter, letters.image, letters.public_likes, letters.date, profiles.name as profile FROM letters INNER JOIN profiles ON letters.profile = profiles.id WHERE public=1;"
  connection.query(query, function(err, rows, fields) {
    if (err) throw err;
    res.json(rows);
  });
});

app.get("/getHospitals", (req, res)=>{
    var query = "SELECT id, name FROM hospitals;";
    connection.query(query, function(err, rows, fields) {
        if (err) throw err;
        res.json(rows);
    });
});

app.get("/", (req, res)=>{
  res.json("API by Palabras Por Sonrisas");
});

app.listen(3267);
