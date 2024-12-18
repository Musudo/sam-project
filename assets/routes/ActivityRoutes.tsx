import {Route, Routes} from "react-router-dom"
import {ActivitiesOverview} from "../components/activities/overview/ActivitiesOverview";
import {ActivityDetails} from "../components/activities/details/ActivityDetails";
import {ActivityCreate} from "../components/activities/form/ActivityCreate";
import {ActivityEdit} from "../components/activities/form/ActivityEdit";
import NotFound from "../components/NotFound";

export default function ActivityRoutes() {
	return (
		<Routes>
			<Route>
				<Route index element={<ActivitiesOverview/>}/>
				<Route path=":guid" element={<ActivityDetails/>}/>
				<Route path="new/:type" element={<ActivityCreate/>}/>
				<Route path="edit/:guid" element={<ActivityEdit/>}/>
				<Route path="*" element={<NotFound/>}/>
			</Route>
		</Routes>
	)
}
