/*
* Hilfsfunktionen
*/
var util = util || {};

// Ganzzahlige Division
util.intDivision = function(dividend, divisor) { return (dividend/divisor)-(dividend%divisor)/divisor; }
