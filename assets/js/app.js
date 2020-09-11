/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
 // loads the jquery package from node_modules
var $ = require('jquery');
require('bootstrap');


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
async function showDays() {
    var date1 = document.getElementById('date1');
    var date2 = document.getElementById('date2');
    document.getElementsByClassName('app').innerHTML = (date2 - date1) / 1000;
}

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
