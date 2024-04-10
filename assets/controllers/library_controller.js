import { Controller } from '@hotwired/stimulus';
import Dexie from 'dexie';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['status']
    // ...

    connect() {
        super.connect();
        console.log('hi from ' + this.identifier);
        var db = new Dexie('library');
        // db.delete().then (()=>db.open());

        db.version(1).stores({
            books: "++isbn,title"
        });
        console.log('async books')
        db.on('ready', async vipDB => {
            const data = await loadData();
            console.log(data);
                const addPromise = await vipDB.books.bulkPut(data).then( (x) => console.log(x));
                console.log ("Done populating.", data);
        });

        console.log('open db');
        db.open();

        db.books.count( (count) => {
            this.statusTarget.innerHTML = count;
        })

        // let timerId = setInterval(this.uploadCached, 1500);



    }

    uploadCached()
    {
        console.log('checking the cache...');
    }
}

    async function loadData()
    {
        let url = 'api/books?page=1';
        const response = await fetch(url);
        // return await response.json().then(data => data)
        return await response.json().then(data => data['hydra:member'])


    }

