
function loaderScript() {

    this.itemsObjects = {};

    this.loadScript = function (url, callback, data) {

        var script = document.createElement("script");
        script.type = "text/javascript";

        if (script.readyState) {  //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" ||
                        script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback(data);
                }
            };
        } else {  //Others
            script.onload = function () {
                callback(data);
            };
        }

        script.src = url;
        this.appendChild(script);
    }

    this.appendChild = function (script) {

        var obj = this;

        setTimeout(function () {

            try {
                document.getElementById("loaderScriptContent").appendChild(script);
            } catch (err) {
                obj.appendChild(script);
            }

        }, 1000);

    }

    this.uploadFiles = function (fileToLoad, callback) {

        this.loopSetTimeout(1000, fileToLoad, this.itemsObjects, callback);
    }

    this.loopSetTimeout = function (timeLoop, fileToLoad, itemsObjects, callback) {

        var obj = this;

        setTimeout(function () {

            if ((activeloaderScript === undefined || activeloaderScript)) {

                for (var i = 0; i < fileToLoad.length; i++) {

                    if (fileToLoad[i].files !== undefined) {

                        var filesLoder = [];

                        obj.loopSetTimeoutSimple(fileToLoad, filesLoder, i, 0);

                        if (fileToLoad[i].url === undefined)
                            var url = '/lib/visualeditorbuild/js/items/' + fileToLoad[i].stringcode + '/' + fileToLoad[i].name + '/itemObject.js';
                        else
                            var url = fileToLoad[i].url;

                        obj.loadScript(url, function (data) {
                            obj.itemsObjects[data.stringcode + data.name] = window["functionSetNewObject"](data.data);
                        }, {"stringcode": fileToLoad[i].stringcode, "name": fileToLoad[i].name, "pos": i, "data": fileToLoad[i].data});

                    } else {

                        if (fileToLoad[i].url === undefined)
                            var url = '/lib/visualeditorbuild/js/items/' + fileToLoad[i].stringcode + '/' + fileToLoad[i].name + '/itemObject.js';
                        else
                            var url = fileToLoad[i].url;

                        obj.loadScript(url, function (data) {
                            obj.itemsObjects[data.stringcode + data.name] = window["functionSetNewObject"](data.data);
                        }, {"stringcode": fileToLoad[i].stringcode, "name": fileToLoad[i].name, "pos": i, "data": fileToLoad[i].data});

                    }

                }

                //callback();
                obj.loopSetTimeoutCallback(1000, fileToLoad, obj.itemsObjects, callback);

            } else {

                obj.loopSetTimeout(timeLoop, fileToLoad, itemsObjects, callback);
            }

        }, timeLoop);

    }

    this.loopSetTimeoutSimple = function (filesToLoad, filesLoder, i, pos) {

        if (filesToLoad[i].files.length == filesLoder.length) {
            return true;
        }

        var obj = this;

        setTimeout(function () {

            obj.loadScript(filesToLoad[i].files[pos], function (data) {

                data.filesLoder.push('up');

                data.pos++;

                obj.loopSetTimeoutSimple(data.filesToLoad, data.filesLoder, data.i, data.pos);

            }, {"filesToLoad": filesToLoad, "filesLoder": filesLoder, "i": i, "pos": pos});

        }, 500);
    }

    this.loopSetTimeoutCallback = function (timeLoop, fileToLoad, itemsObjects, callback) {

        var obj = this;

        setTimeout(function () {

            if ((activeloaderScript === undefined || activeloaderScript) && fileToLoad.length == Object.keys(itemsObjects).length) {

                callback();

            } else {

                obj.loopSetTimeoutCallback(timeLoop, fileToLoad, itemsObjects, callback);
            }

        }, timeLoop);

    }

    this.getItemsObjects = function () {

        return this.itemsObjects;
    }
}