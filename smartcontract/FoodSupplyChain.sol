pragma solidity ^0.6.0;

contract FoodSupplyChain {
    
    event Added(uint256 index);
    
    struct State{
        string details;
        address user;
    }
    
    struct FoodProduct{
        address creator;
        string productName;
        uint256 productId;
        string date;
        uint256 totalStates;
        mapping (uint256 => State) positions;
    }
    
    mapping(uint => FoodProduct) allProducts;
    uint256 items=0;
    
    function concat(string memory _a, string memory _b) public returns (string memory){
        bytes memory bytes_a = bytes(_a);
        bytes memory bytes_b = bytes(_b);
        string memory length_ab = new string(bytes_a.length + bytes_b.length);
        bytes memory bytes_c = bytes(length_ab);
        uint k = 0;
        for (uint i = 0; i < bytes_a.length; i++) bytes_c[k++] = bytes_a[i];
        for (uint i = 0; i < bytes_b.length; i++) bytes_c[k++] = bytes_b[i];
        return string(bytes_c);
    }
    
    function newItem(string memory _text, string memory _date) public returns (bool) {
        FoodProduct memory newItem = FoodProduct({creator: msg.sender, totalStates: 0,productName: _text, productId: items, date: _date});
        allProducts[items]=newItem;
        items = items+1;
        emit Added(items-1);
        return true;
    }
    
    function addState(uint _productId, string memory info) public returns (string memory) {
        require(_productId<=items);
        
        State memory newState = State({user: msg.sender, details: info});
        
        allProducts[_productId].positions[ allProducts[_productId].totalStates ]=newState;
        
        allProducts[_productId].totalStates = allProducts[_productId].totalStates +1;
        return info;
    }
    
    function searchProduct(uint _productId) public returns (string memory) {

        require(_productId<=items);
        string memory output="<b>Product Name: </b>";
        output=concat(output, allProducts[_productId].productName);
        output=concat(output, "<br><b>Sanctioned Date: </b>");
        output=concat(output, allProducts[_productId].date);
       
        for (uint256 j=0; j<allProducts[_productId].totalStates; j++){
            output=concat(output, allProducts[_productId].positions[j].details);
        }

        return output;
        
    }
    
}