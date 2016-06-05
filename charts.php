<html>
    <head>
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/d3/3.2.2/d3.v3.min.js"></script>
        <script type="text/javascript" src=inc/uvcharts/uvcharts.full.min.js></script>
    </head>
    <body>
        <div id="uv-div"></div>
        <script type="application/javascript">
        var graphdef = {
            categories : ['uvCharts'],
            dataset : {
                'uvCharts' : [
                    { name : '2009', value : 32 },
                    { name : '2010', value : 60 },
                    { name : '2011', value : 97 },
                    { name : '2012', value : 560 },
                    { name : '2013', value : 999 }
                ]
            }
        }
        var chart = uv.chart ('Bar', graphdef, {
            meta : {
                caption : 'Usage over years',
                subcaption : 'among Imaginea OS products',
                hlabel : 'Years',
                vlabel : 'Number of users',
                vsublabel : 'in thousands'
            }
        })
        </script>
        
    </body>
</html>