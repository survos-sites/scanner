import { Controller } from '@hotwired/stimulus';
import {Html5QrcodeScanner} from 'html5-qrcode';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['results', 'camera']
    static values = {
        testIsbn: String,
    }

    connect() {
        super.connect();
        this.resultsTarget.innerHTML = this.testIsbnValue;
        this.fetchInfo(this.testIsbnValue);


        var html5QrcodeScanner = new Html5QrcodeScanner(
            // this.cameraTarget.element.attribute('id'),
            'qr-reader',
            { fps: 10});
        html5QrcodeScanner.render( (decodedText, decodedResult) => {
            this.resultsTarget.innerHTML = decodedText;
            // if it's isbn...
            const info = this.fetchInfo(decodedText);
            console.log(`Code scanned = ${decodedText}`, decodedResult);
        });

    }

    async fetchInfo(id)
    {
        if (id.startsWith('978')) {
        }
            let url = "/isbn/" + id;
            const response = await fetch(url);
            const info = await response.json().catch(error => console.error(error));
            this.resultsTarget.innerHTML = info.title;
            console.log(info);
    }

    // ...
}
