import React, {useEffect, useState} from 'react';
import {IUser} from '../models/IUser';
import UserContext from './UserContext';
import axios from 'axios';

interface UserContextProviderProps {
	children: React.ReactNode;
}

export default function UserProvider({children}: UserContextProviderProps) {
	const [user, setUser] = useState<IUser | null>(null);

	useEffect(() => {
		fetchGuidAndUser();
	}, []);

	const fetchGuidAndUser = async () => {
		try {
			// get guid which is passed with user-header
			const guid = await axios.get(document.URL)
				.then((response) => response.headers['user-header']);

			if (guid !== 'Not authorized') {
				const userData = await axios.get(`/users/${guid}`)
					.then((response) => response?.data);

				setUser(userData);
			}
		} catch (error) {
			console.error('Error fetching data:', error);
		}
	};

	return (
		<UserContext.Provider value={{user}}>
			{children}
		</UserContext.Provider>
	);
}
