import {BrowserRouter, Route, Routes} from 'react-router-dom'
import NotFound from "../components/NotFound";
import ActivityRoutes from "./ActivityRoutes";
import AppOutlet from "../components/AppOutlet";
import ContactRoutes from "./ContactRoutes";
import {Dashboard} from "../components/dashboard/Dashboard";

export default function Router() {
	return (
		<BrowserRouter>
			<Routes>
				<Route element={<AppOutlet/>}>
					<Route path="/" element={<Dashboard/>}/>
					<Route path="/dashboard" element={<Dashboard/>}/>
					<Route path="/activities/*" element={<ActivityRoutes/>}/>
					<Route path="/contacts/*" element={<ContactRoutes/>}/>
					<Route path="*" element={<NotFound/>}/>
				</Route>
			</Routes>
		</BrowserRouter>
	)
}
