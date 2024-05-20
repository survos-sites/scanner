// import { Controller } from '@hotwired/stimulus';
import MobileController from '@survos-mobile/mobile';
export default class extends MobileController {

    getProjectFiltered() {
        return [];
    }

    isPopulated() {
        return true;
    }
}
