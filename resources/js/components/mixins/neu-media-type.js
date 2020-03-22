import axios from "axios";
export default {
	methods: {		
		getPartMediaTypes (partMediaTypeLoad, parts) {	 			            
			return axios.post(partMediaTypeLoad, {parts:parts}).then(mediatypeResult => {
				return [mediatypeResult.data.data.part_media_types, mediatypeResult.data.data.media_type_empty];
			});				
		}
	}
}