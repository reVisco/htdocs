<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">

    </head>
    <body>
        
        <script>
            import jsQR from "jsqr";

            const image = getImageData(); // This is your image data
            const code = jsQR(image.data, image.width, image.height);

            if (code) {
            console.log("Found QR code", code);
            }
        </script>
    </body>
</html>