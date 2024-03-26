import { startStimulusApp } from '@symfony/stimulus-bundle';
import ContentLoader from '@stimulus-components/content-loader'
const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register('content-loader', ContentLoader)
