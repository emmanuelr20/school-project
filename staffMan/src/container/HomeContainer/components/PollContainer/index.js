// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import Poll from "../../../../stories/screens/Home/components/Poll";


@inject("mainStore")
@observer
export default class PollContainer extends React.Component {
	render() {
		return <Poll {...this.props}  />;
	}
}
