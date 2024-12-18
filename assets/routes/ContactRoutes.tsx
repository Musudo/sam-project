import {Route, Routes} from "react-router-dom"
import {ContactsOverview} from "../components/contacts/overview/ContactsOverview";
import {ContactDetails} from "../components/contacts/details/ContactDetails";
import {ContactCreate} from "../components/contacts/form/ContactCreate";
import {ContactEdit} from "../components/contacts/form/ContactEdit";
import NotFound from "../components/NotFound";

export default function ContactRoutes() {
	return (
		<Routes>
			<Route>
				<Route index element={<ContactsOverview/>}/>
				<Route path=":guid" element={<ContactDetails/>}/>
				<Route path="new" element={<ContactCreate/>}/>
				<Route path="new/:external" element={<ContactCreate/>}/>
				<Route path="edit/:guid" element={<ContactEdit/>}/>
				<Route path="*" element={<NotFound/>}/>
			</Route>
		</Routes>
	)
}
