<?php

class ImportController extends Controller
{
	public function filters()
	{
		return array(
			'rights',
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function allowedActions()
	{
		return 'importdata';
	}
	
	// Actions
    public function actionImportData($key)
    {
		if($key=='KsJyg9APMwaCZxEKcg')
		{
			$lastReadVenueID;
			$lastReadSalesID;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//import venues
			while(1)
			{
				//Select data feed and read parameters
				$Systemparameter=Systemparameter::model()->find('parameterName=:parameterName',array(':parameterName'=>'TTTCLastReadVenueID'));
				$lastReadVenueID=$Systemparameter->parameterValue;
				curl_setopt($ch, CURLOPT_URL, 'https://account.ticketstothecity.com/api/theatreprofile-venues.php?KEY='.$key.'&last='.$lastReadVenueID.'&count=100');
				//Read data
				$result = curl_exec($ch);
				$obj = json_decode($result);
				if($obj->success)
				{
					if($obj->count==0)
						break;
					for ($i=0;$i <$obj->count;$i++)
					{
						$data=$obj->data[$i];
						//Save data
						$lastReadVenueID=$data->VenueID;
						$ImportTTTCVenue=new ImportTTTCVenue;
						$ImportTTTCVenue->venueID=$data->VenueID;
						$ImportTTTCVenue->venueName=strip_tags($data->VenueName);
						$ImportTTTCVenue->capacity=$data->Capacity;
						$ImportTTTCVenue->address=strip_tags($data->Address);
						$ImportTTTCVenue->city=strip_tags($data->City);
						$ImportTTTCVenue->state=strip_tags($data->State);
						$ImportTTTCVenue->latitude=$data->Latitude;
						$ImportTTTCVenue->longitude=$data->Longitude;
						$ImportTTTCVenue->organizationID=$data->OrganizationID;
						$ImportTTTCVenue->organization=strip_tags($data->Organization);
						$ImportTTTCVenue->source=1;
						$ImportTTTCVenue->status=0;
						if($ImportTTTCVenue->save())
						{
							//Update read parameters
							$Systemparameter->parameterValue=$lastReadVenueID;
							$Systemparameter->save();
						}
					}
				}
				else
				{
					//Report error
					throw new CHttpException(666,'Error processing TTTC API data.');
					break;
				}
			}
			//import ticket sales
			while(1)
			{
				//Select data feed and read parameters
				$Systemparameter=Systemparameter::model()->find('parameterName=:parameterName',array(':parameterName'=>'TTTCLastReadSalesID'));
				$lastReadSalesID=$Systemparameter->parameterValue;
				curl_setopt($ch, CURLOPT_URL, 'https://account.ticketstothecity.com/api/theatreprofile-sales.php?KEY='.$key.'&last='.$lastReadSalesID.'&count=100');
				//Read data
				$result = curl_exec($ch);
				$obj = json_decode($result);
				if($obj->success)
				{
					if($obj->count==0)
						break;
					for ($i=0;$i <$obj->count;$i++)
					{
						$data=$obj->data[$i];
						//Save data
						$lastReadSalesID=$data->SalesID;
						$ImportTTTCTicketSale=new ImportTTTCTicketSale;
						$ImportTTTCTicketSale->salesID=$data->SalesID;
						$ImportTTTCTicketSale->venueID=$data->VenueID;
						$ImportTTTCTicketSale->purchaseDate=$data->PurchaseDate;
						$ImportTTTCTicketSale->boxOffice=$data->BoxOffice;
						$ImportTTTCTicketSale->boxOfficeComp=$data->BoxOfficeComp;
						$ImportTTTCTicketSale->refundInventory=$data->RefundInventory;
						$ImportTTTCTicketSale->refundSale=$data->RefundSale;
						$ImportTTTCTicketSale->refundDate=$data->RefundDate;
						$ImportTTTCTicketSale->refundReason=strip_tags($data->RefundReason);
						$ImportTTTCTicketSale->title=strip_tags($data->Title);
						$ImportTTTCTicketSale->eventID=$data->EventID;
						$ImportTTTCTicketSale->eventDate=$data->EventDate;
						$ImportTTTCTicketSale->section=strip_tags($data->Section);
						$ImportTTTCTicketSale->ticket=strip_tags($data->Ticket);
						$ImportTTTCTicketSale->qty=$data->QTY;
						$ImportTTTCTicketSale->seat=strip_tags($data->Seat);
						$ImportTTTCTicketSale->first=$data->First;
						$ImportTTTCTicketSale->last=$data->Last;
						$ImportTTTCTicketSale->name=$data->Name;
						$ImportTTTCTicketSale->billingName=$data->BillingName;
						$ImportTTTCTicketSale->billingAddress1=$data->BillingAddress1;
						$ImportTTTCTicketSale->billingAddress2=$data->BillingAddress2;
						$ImportTTTCTicketSale->billingCity=$data->BillingCity;
						$ImportTTTCTicketSale->billingState=$data->BillingState;
						$ImportTTTCTicketSale->billingZip=$data->BillingZip;
						$ImportTTTCTicketSale->email=$data->Email;
						$ImportTTTCTicketSale->phone=$data->Phone;
						$ImportTTTCTicketSale->transactionID=$data->TransactionID;
						$ImportTTTCTicketSale->invoiceID=$data->InvoiceID;
						$ImportTTTCTicketSale->subtotal=$data->Subtotal;
						$ImportTTTCTicketSale->fees=$data->Fees;
						$ImportTTTCTicketSale->netTotal=$data->NetTotal;
						$ImportTTTCTicketSale->BOOrderDiscount=$data->BOOrderDiscount;
						$ImportTTTCTicketSale->organizationID=$data->OrganizationID;
						$ImportTTTCTicketSale->organization=$data->Organization;
						$ImportTTTCTicketSale->source=1;
						$ImportTTTCTicketSale->userID=null;
						$ImportTTTCTicketSale->status=0;
						if($ImportTTTCTicketSale->save())
						{
							//Update read parameters
							$Systemparameter->parameterValue=$lastReadSalesID;
							$Systemparameter->save();
						}
					}
				}
				else
				{
					//Report error
					throw new CHttpException(666,'Error processing TTTC API data.');
					break;
				}
			}
			curl_close($ch);
		}
		else
		{
			throw new CHttpException(401,'Invalid import key.');
		}
	}
}