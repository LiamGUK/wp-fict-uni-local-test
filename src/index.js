// Using JS files in Wordpress need to include a file called index.js contained inside a src folder - wordpress scripts package looks specifically for this file location
// When running build command with wordpress scripts will package and bundle all js files into the build folder

// wordpress scripts auto includes sass packages so can add sass file link in index.js file here and will compile on bundle
import "../css/style.scss";

// Our modules / classes
import MobileMenu from "./modules/MobileMenu";
import HeroSlider from "./modules/HeroSlider";
import GoogleMap from "./modules/GoogleMap";
import Search from "./modules/Search";
import MyNotes from "./modules/MyNotes";
import Like from "./modules/Like";

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu();
const heroSlider = new HeroSlider();
const googleMap = new GoogleMap();
const liveSearch = new Search();
const myNotes = new MyNotes();
const like = new Like();
