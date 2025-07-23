import React, { useState } from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

const Home = () => {

    const [modal, setModal] = useState(false);

    const toggle = () => setModal(!modal);
    return (
        <div>

        </div>
    )
}

export default Home