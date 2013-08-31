var d = {
    // Attribut für das Canvas-Objekt
    canvas : null,

    // 2D-Grafikkontext
    context : null,
    
    // Konstruktor
    init: function(elementId) {
        d.canvas = document.getElementById(elementId);
        d.context = d.canvas.getContext("2d");
    },

    // Diagramm zeichnen
    drawDiagramFor: function(values) {
        var first = values[0];
        var max = d.util.getMax(values) - first;
        var min = d.util.getMin(values) - first;
        var absMax = d.util.getAbs(min)>d.util.getAbs(max)?d.util.getAbs(min):d.util.getAbs(max);
        var stepSize = 400 / (values.length - 1);
        var scaleFactor = 90 / absMax;
        var currentStep = 0;
        // Fläche neuzeichnen
        d.drawRect(0, 0, 400, 200);
        d.drawLine(0, 100, 400, 100);
        // Schrittweise zeichnen
        var ctx = d.context;
        ctx.fillStyle = "darkblue"; //"hsla(30,80%,60%,4.9)";
        ctx.strokeStyle = "darkblue"; //"hsla(30,80%,60%,4.9)";
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.lineTo(0, 100);
        for(var idx in values) {
            var val = (values[idx] - first) * scaleFactor;
            ctx.lineTo(currentStep, 100 - val);
            ctx.fillRect (currentStep - 5, 100 - val - 5, 10, 10);
            ctx.stroke();
            currentStep += stepSize;
            //console.log("Value["+idx+"]="+val);
            //console.log("Y="+currentStep);
        }
        ctx.stroke();
        ctx.lineTo(400, 100);
        ctx.closePath();
        ctx.stroke();
        // Debug-Ausgabe
        //console.log("Min:"+min+" Max:"+max);
        //console.log("Schritt:"+stepSize);
        //console.log("Skalierung:"+scaleFactor);
    },

    // Begin-Utilities
    util : {    
    // Maximum ermitteln
    getMax: function(values) {
        var max = 'null';
        for(var idx in values) {
            var val = values[idx];
            if(max === 'null') {
                max = val;
            }
            if(max < val) {
                max = val;
            }
        }
        return max;
    },

    // Minimum ermitteln
    getMin: function(values) {
        var min = 'null';
        for(var idx in values) {
            var val = values[idx];
            if(min === 'null') {
                min = val;
            }
            if(min > val) {
                min = val;
            }
        }
        return min;
    },
    
    // Berechnet den Absolut-Betrag der angeg. Zahl
    getAbs: function(value) {
        if(value >= 0) {
            return value;
        } else {
            return value *-1;
        }
    },
    },
    // End-Utilities

    // Hellblaues Rechteck zeichnen (fuer Hintergrund)
    drawRect: function(x1, y1, x2, y2) {
        if(!!d.context) {
            d.context.fillStyle = "lightblue";
            d.context.lineStyle = 3;
            d.context.fillRect(x1, y1, x2 - x1, y2 - y1); 
        } else {
            alert('Fehler: d ist nicht initialisiert');
        }
    },

    // Linie zeichnen
    drawLine: function(x1, y1, x2, y2) {
        if(!!d.context) {
            var ctx = d.context;
            ctx.strokeStyle = "hsla(30,80%,60%,0.9)";
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.lineTo(x1, y1);
            ctx.stroke();
            ctx.lineTo(x2, y2);
            ctx.stroke();
            ctx.fill();
            ctx.closePath();
            ctx.stroke();
        } else {
            alert('Fehler: d ist nicht initialisiert');
        }
    },

};

