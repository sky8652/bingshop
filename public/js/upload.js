/** * 上传图片 * * @param file * @param successCall * @param progressCall * @param errorCall * @param zone */function uploadPicture(file,successCall,progressCall,errorCall,zone) {    let config = {        useCdnDomain: true,        disableStatisticsReport: false,        retryCount: 6,        region: zone        //qiniu.region.z2    };    let fileName = file.name;    let timestamp = Date.parse(new Date());    let random = Math.round(Math.random()*10000);    let key = "hui_yi_"+timestamp+random;    let putExtra = {        fname: fileName,        params: {},        mimeType: null    };    let error = function(err) {        if(errorCall != "undefined"){            errorCall(err);        }    };    let complete = function(res) {        successCall(res);    };    let next = function(response) {        let total = response.total;        if(progressCall != "undefined"){            progressCall(response);        }    };    let observable = qiniu.upload(file, key, token, putExtra, config);    let subObject = {        next: next,        error: error,        complete: complete    };    observable.subscribe(subObject);}